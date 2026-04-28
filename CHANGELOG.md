## [3.1.3] - 2026-04-28

- Choice fields: POST values limited to `choices` keys when set.
- Multicheck: trailing hidden `[]` so all unchecked saves `[]`.
- `add_field()`: save merged args, require `id`, return `false` if skipped.
- `get_field_by_id()`: match storage key or `field['id']`.

## [3.1.2] - 2026-04-27

- Add plain-text CodeMirror support for `code` fields (`mime_type` => `text`)

## [3.1.1] - 2025-03-09

- Use WordPress admin theme color variables

## [3.1.0] - 2025-07-24

- Added `extra_attrs` support for text-based fields
- Improve PHP docs

## [3.0.0] - 2024-07-21

- Minimum PHP requirement: 7.2
- Code optimization
- CSS refinement

## [2.0.0] - 2022-09-06

- Modern design
- Add new fields
- Add hooks for rendering fields

## [1.0.0] - 2020-06-18

- Initial release
