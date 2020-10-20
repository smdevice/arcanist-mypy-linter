# arcanist-mypy-linter

Python Mypy type checker for arcanist.

## Installation

Mypy is required.

    pip3 install mypy

### Project-specific installation

You can add this repository as a git submodule. Add a path to the submodule in your `.arcconfig` like so:

```json
{
  "load": ["path/to/arcanist-mypy-linter"]
}
```

### Global installation

`arcanist` can load modules from an absolute path. But it also searches for modules in a directory
up one level from itself.

You can clone this repository to the same directory where `arcanist` are located.
In the end it will look like this:

```sh
arcanist/
arcanist-mypy-linter/
```

Your `.arcconfig` would look like

```json
{
  "load": ["arcanist-mypy-linter"]
}
```

## Setup

To use the linter you must register it in your `.arclint` file.

```json
{
  "linters": {
    "mypy": {
      "type": "mypy",
      "include": "(\\.py$)"
    }
  }
}
```

## License

Licensed under the Apache 2.0 license. See LICENSE for details.