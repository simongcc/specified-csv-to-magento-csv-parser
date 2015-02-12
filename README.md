This is A Parser for converting a CSV in specified format to magento compatible product CSV.

##Preparations
1. Assume your product is already exist in Magento
2. A CSV with multiple price input in specified format

##Demo Format for the specified CSV
You may refer to [this google spreadsheet(read only)](http://goo.gl/eTHzn6) for the format.
This format is convenient for input multiple price for different groups and set formaula.

##Usage
1. Place the CSV in specified format together with the parser.
2. Run the parser, eg. http://yourdomain.com/parse_csv.php
3. The processed CSV would be placed in the same folder
4. Upload the processed CSV to Magento in Product Import

##Possible Improvement for this parser
* add an uploader and submit mechanism to interactively upload
* after parsing the CSV, download the CSV automatically to local drive

This CSV parser is developed for a client Magento project as a solution for multiple price input.
I hope this piece of code can inspire or help other people so I place it here with a MIT license.

## License
Copyright (c) 2014-2015 Ng Simon. Licensed under the [MIT license](https://github.com/simongcc/specified-csv-to-magento-csv-parser/blob/master/LICENSE.md).
