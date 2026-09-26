<?php

namespace DorsetDigital\Elements\Dev;

use DorsetDigital\Elements\ImageTextElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\BuildTask;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\SiteTreeLink;
use SilverStripe\ORM\DB;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class MigrateImageTextElementTask extends BuildTask
{
    protected static string $commandName = 'MigrateImageTextElementTask';

    protected string $title = 'Migrate Image & Text elements to the v2 data model';

    protected static string $description = 'Migrates legacy many-many images and SiteTree image links to the v2 has-one and LinkField relations.';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $output->writeln('<info>Starting Image & Text element v2 migration.</info>');
        $output->writeln('This task is non-destructive: legacy relation data is left in place.');

        $imageStats = $this->migrateImages($output);
        $linkStats = $this->migrateLinks($output);

        $output->writeln('');
        $output->writeln(sprintf(
            '<info>Images: %d migrated, %d already populated, %d warnings.</info>',
            $imageStats['migrated'],
            $imageStats['skipped'],
            $imageStats['warnings']
        ));
        $output->writeln(sprintf(
            '<info>Links: %d migrated, %d already migrated/empty, %d warnings.</info>',
            $linkStats['migrated'],
            $linkStats['skipped'],
            $linkStats['warnings']
        ));

        return Command::SUCCESS;
    }

    private function migrateImages(PolyOutput $output): array
    {
        $stats = ['migrated' => 0, 'skipped' => 0, 'warnings' => 0];
        $table = $this->findLegacyImageTable();

        if (!$table) {
            $output->writeln('<comment>No legacy image join table found; skipping image migration.</comment>');
            return $stats;
        }

        $fields = array_keys(DB::get_schema()->fieldList($table));
        $imageColumn = in_array('ImageID', $fields, true) ? 'ImageID' : null;
        $ownerColumns = array_values(array_filter(
            $fields,
            static fn (string $field): bool => $field !== 'ID' && $field !== 'ImageID' && str_ends_with($field, 'ID')
        ));

        if (!$imageColumn || count($ownerColumns) !== 1) {
            $output->writeln(sprintf(
                '<error>Could not safely identify columns in legacy image table "%s". Fields: %s</error>',
                $table,
                implode(', ', $fields)
            ));
            $stats['warnings']++;
            return $stats;
        }

        $ownerColumn = $ownerColumns[0];
        $rows = DB::query(sprintf(
            'SELECT "%s", "%s" FROM "%s" ORDER BY "ID" ASC',
            $ownerColumn,
            $imageColumn,
            $table
        ));

        $legacyImages = [];
        foreach ($rows as $row) {
            $ownerID = (int) $row[$ownerColumn];
            $imageID = (int) $row[$imageColumn];
            if ($ownerID && $imageID) {
                $legacyImages[$ownerID][] = $imageID;
            }
        }

        foreach ($legacyImages as $elementID => $imageIDs) {
            $element = ImageTextElement::get()->byID($elementID);
            if (!$element) {
                $output->writeln(sprintf(
                    '<comment>Image warning: element #%d no longer exists; legacy image(s) left untouched.</comment>',
                    $elementID
                ));
                $stats['warnings']++;
                continue;
            }

            if ((int) $element->getField('ImageID') > 0) {
                $stats['skipped']++;
                continue;
            }

            $imageID = reset($imageIDs);
            $element->setField('ImageID', $imageID);
            $element->write();
            $stats['migrated']++;

            if (count($imageIDs) > 1) {
                $output->writeln(sprintf(
                    '<comment>Image warning: element #%d had %d legacy images. Migrated image #%d only.</comment>',
                    $elementID,
                    count($imageIDs),
                    $imageID
                ));
                $stats['warnings']++;
            }
        }

        return $stats;
    }

    private function migrateLinks(PolyOutput $output): array
    {
        $stats = ['migrated' => 0, 'skipped' => 0, 'warnings' => 0];

        foreach (ImageTextElement::get() as $element) {
            $legacyID = (int) $element->getField('ImageLinkID');
            if (!$legacyID) {
                $stats['skipped']++;
                continue;
            }

            $existingLink = Link::get()->byID($legacyID);
            if (
                $existingLink
                && (int) $existingLink->OwnerID === (int) $element->ID
                && $existingLink->OwnerClass === ImageTextElement::class
                && $existingLink->OwnerRelation === 'ImageLink'
            ) {
                $stats['skipped']++;
                continue;
            }

            $page = SiteTree::get()->byID($legacyID);
            if (!$page) {
                $output->writeln(sprintf(
                    '<comment>Link warning: element #%d references legacy SiteTree #%d, which was not found. Value left untouched.</comment>',
                    $element->ID,
                    $legacyID
                ));
                $stats['warnings']++;
                continue;
            }

            $link = SiteTreeLink::create();
            $link->PageID = $page->ID;
            $link->OwnerID = $element->ID;
            $link->OwnerClass = ImageTextElement::class;
            $link->OwnerRelation = 'ImageLink';
            $link->write();

            $element->setField('ImageLinkID', $link->ID);
            $element->write();
            $stats['migrated']++;
        }

        return $stats;
    }

    private function findLegacyImageTable(): ?string
    {
        $candidates = [
            'DorsetDigital_Elements_ImageText_Image',
            '_obsolete_DorsetDigital_Elements_ImageText_Image',
        ];

        foreach ($candidates as $table) {
            if (DB::get_schema()->hasTable($table)) {
                return $table;
            }
        }

        return null;
    }
}
