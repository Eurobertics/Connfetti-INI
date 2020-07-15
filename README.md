# Connfetti-INI
A INI config file reader in PHP which returns the content of the INI file as a class hierarchy.

Connfetti-INI is a simple INI reader which reads an INI file with a syntax as follows:

```ini
configname=configvalue
configname.subconfigname=configvalue2
```
and returns a class with the hierarchical readonly members of the config variables, for example:

```php
$config = new Config('test.ini');
echo $config->configname;
echo "\n";
echo $config->configname->subconfigname;
```
Returns:
```
configvalue
configvalue2
```

**Requires [Connfetti-IO](https://github.com/Eurobertics/Connfetti-IO) for reading the files.**