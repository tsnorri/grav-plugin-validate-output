# Validate Output Plugin

The **Validate Output** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It passes the HTML output to DOMDocument or Tidy and logs any problems as warnings to the Grav log.

## Installation

Installing the Validate Output plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install validate-output

This will install the Validate Output plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/validate-output`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `validate-output`. You can find these files on [GitHub](https://github.com/tsnorri/grav-plugin-validate-output) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/validate-output
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/validate-output/validate-output.yaml` to `user/config/plugins/validate-output.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
use\_dom\_document: true
use\_tidy: true
```

## Usage

Enable the plugin and set any of the other options to `true`.
