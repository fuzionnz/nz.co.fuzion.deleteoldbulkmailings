# Delete Old Bulk Mailings for CiviCRM

Reduce the size of your CiviCRM DB by removing historic bulk mailing data.

## Installation

Refer to [CiviCRM Extensions Documentation](https://docs.civicrm.org/user/en/latest/introduction/extensions/)

## Configuration

No special configuration is required for this extension. Just install it!

## Usage

This extension exposes a new API method (`Bulkmailing.Deleteoldrecords`). This method accepts either a comma-separated list of mailing IDs to delete, or a date (for which it will delete any mails scheduled for delivery before that).

```text
# Delete records for mailings 123, 456 and 789
$ cv api Bulkmailing.Deleteoldrecords mailing_ids=123,456,789
# Delete all mailings scheduled before 2012
$ cv api Bulkmailing.Deleteoldrecords delivered_date_before=2011-01-01
```

After installation this is also available as a [CiviCRM Scheduled Job](https://docs.civicrm.org/sysadmin/en/latest/setup/jobs/) under the name "Delete Old Bulk Mailings".

## License

Copyright (C) 2018, Jitendra Purohit <jitendra@fuzion.co.nz>, licensed under the GNU Affero Public License 3.0. See [LICENSE.md](LICENSE.md).

## Credits

This extension was written by Jitendra Purohit ([@jitendrapurohit](https://github.com/jitendrapurohit)) of [Fuzion](https://www.fuzion.co.nz).

## Supporting Organisations

This extension is contributed by [Fuzion](https://www.fuzion.co.nz).

We welcome contributions and bug reports via the [nz.co.fuzion.deleteoldbulkmailings issue queue](https://github.com/fuzionnz/nz.co.fuzion.deleteoldbulkmailings/issues).

Community support is available via CiviCRM community channels:

* [CiviCRM chat](https://chat.civicrm.org)
* [CiviCRM question & answer forum on StackExchange](https://civicrm.stackexchange.com/)

Contact us - [info@fuzion.co.nz](mailto:info@fuzion.co.nz) - for professional support and development requests.