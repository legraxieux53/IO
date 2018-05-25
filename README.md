# IO
Manage all Inputs / Output flow 

## Install
`composer require legracieux53/io`

## Usage
1. Import in your file
```php
namespace MyNameSpace;

... 
use Lnl\IO\Files\File as MyFile;

...
```

2. Enjoy

```php
	$file = new MyFile();
    $file->init("public/my_file.txt");
    $file->create();
```

## Documentation

### init ($full_name)
Init the file by setting is full name. example : `"public/sub_dir/my_file.txt"` for file in `public` path.

### create()
Create the file has set by the `init` function if not exist.

### writeOver($content)
Write in file by replacing it content

### write($content)
Write in file by adding new content

### writeBase64 ($content)
Write base64 string in file like `*.jpg` or `*.png` files.

### read ()
Get content of file.

### delete()
Delete file. return `FILE_DELETE_SUCCESS` or `FILE_DELETE_ERROR`.

### deleteLine ($line)
Delete line or ocurence in file.

### generateName ()
Generate name for file

### gerUrlName ()
Get absolute url of file