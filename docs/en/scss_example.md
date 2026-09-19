# Styling example

This module deliberately does not provide frontend CSS. Layout is expected to be handled by the consuming project's stylesheet.

The default width values are `half`, `third`, `quarter` and `sixth`. A simple framework-independent starting point could be:

```scss
.content-element__row {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.content-element__column {
  flex: 1 1 0;
}

.content-element__column.half {
  flex-basis: calc(50% - 1rem);
}

.content-element__column.third {
  flex-basis: calc(33.333% - 1rem);
}

.content-element__column.quarter {
  flex-basis: calc(25% - 1rem);
}

.content-element__column.sixth {
  flex-basis: calc(16.667% - 1rem);
}

.content-image {
  display: block;
  max-width: 100%;
  height: auto;
}

@media (max-width: 767px) {
  .content-element__column {
    flex-basis: 100%;
  }
}
```

The width keys are configurable, so projects using Bootstrap or another layout system can instead configure the dropdown values to emit their own utility or grid classes.
