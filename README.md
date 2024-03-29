# Vanilla Comments plugin

[![Bludit](https://img.shields.io/badge/Bludit-v3.9.2-0078D4.svg?style=flat-square)](https://www.bludit.com)
[![Vanilla Forums](https://img.shields.io/badge/Vanilla-v2.x/v3.x-0078D4.svg?style=flat-square)][vanilla]
![Version](https://img.shields.io/github/tag/DivineDominion/vanilla-comments.svg?style=flat)
![License](https://img.shields.io/github/license/DivineDominion/vanilla-comments.svg?style=flat)

Use your existing [Vanilla Forum][vanilla] to embed comments on pages in your Bludit flat-file CMS.

[vanilla]: https://vanillaforums.com/

## Features

- Configure the position where the Vanilla comment embed code is displayed.
- Conditionally allow/disallow comments for individual pages. The Bludit system default is "allow".

## Requirements

- Vanilla Forum v2.x and v3.x with the [embed settings enabled](https://docs.vanillaforums.com/help/embedding/)
- Bludit v3.5.0+

## Installation

- Download the the current [`master` as a zip](https://github.com/DivineDominion/BluditVanillaComments/zipball/master)
- Unzip the package
- Upload the resulting `vanilla-comments` folder to your `bl-plugins/` folder
- Visit the Bludit admin page and enable the "Vanilla Comments" plugin through "Settings" > "Plugins" 
- Configure the forum settings from the plugins page

### Manual Discussion ID

Discussions are auto-generated by default.

To manually set the `vanillaDiscussionId` field in the comments section, add custom fields to Bludit:

- `customDiscussionID`: that holds the discussion ID (number), which suffices to attach a _new_ post to an existing discussion;
- `customVanillaIdentifier`: that holds the slug of another post to steal discussions from.

```json
"customDiscussionID": {
  "type": "string",
  "label": "Forum Discussion ID Override",
  "placeholder": "(Optional: Existing forum discussion ID)",
  "tip": "<b>Leave empty to auto-generate discussions!</b> Attach to a pre-existing discussion. Copy the number in the URL of an existing discussion like <code>123</code> in <code>forum.example.com/discussion/123/...</code>."
},
"customVanillaIdentifier": {
  "type": "string",
  "label": "Forum Identifier Override",
  "placeholder": "(Optional: URL of other post)"
}
```

See the docs: https://docs.bludit.com/en/content/custom-fields 

## License

Copyright &copy; 2019 Christian Tietze. Published under the MIT-License. See the [LICENSE](LICENSE) file.
