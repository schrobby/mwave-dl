mwave-dl
========

Download Meet & Greet from [Mwave](http://mwave.interest.me/meetgreet/) easily via the command line. 

# Requirements

* [PHP 5](http://php.net/downloads.php)
* [libcurl with PHP bindings](http://curl.haxx.se/libcurl/php/)
* [AdobeHDS.php by K-S-V](https://github.com/K-S-V/Scripts/blob/master/AdobeHDS.php)

# Usage

```
Usage: mwave-dl.php <url> [--resolution {360p, 480p, 720p, 1080p}] [adobehds_params]

                            KSV Adobe HDS Downloader

You can use script with following switches:

 --help              displays this help
 --debug             show debug output
 --delete            delete fragments after processing
 --fproxy            force proxy for downloading of fragments
 --play              dump stream to stdout for piping to media player
 --rename            rename fragments sequentially before processing
 --update            update the script to current git version
 --auth      [param] authentication string for fragment requests
 --duration  [param] stop recording after specified number of seconds
 --filesize  [param] split output file in chunks of specified size (MB)
 --fragments [param] base filename for fragments
 --fixwindow [param] timestamp gap between frames to consider as timeshift
 --manifest  [param] manifest file for downloading of fragments
 --maxspeed  [param] maximum bandwidth consumption (KB) for fragment downloading
 --outdir    [param] destination folder for output file
 --outfile   [param] filename to use for output file
 --parallel  [param] number of fragments to download simultaneously
 --proxy     [param] proxy for downloading of manifest
 --quality   [param] selected quality level (low|medium|high) or exact bitrate
 --referrer  [param] Referer to use for emulation of browser requests
 --start     [param] start from specified fragment
 --useragent [param] User-Agent to use for emulation of browser requests
```

# Examples

Find the available download options for this particular [Meet & Great video](http://mwave.interest.me/meetgreet/view/114):

```
$ ./mwave-dl.php http://mwave.interest.me/meetgreet/view/114
Title: [MEET&GREET] Strawberry Milk
Views: 1082

Available quality options:
        720p, bitrate: 2M
        480p, bitrate: 1M
```

Download the 720p version and save it as `meetgreet.flv`:

```
$ ./mwave-dl.php http://mwave.interest.me/meetgreet/view/114 --resolution 720p --outfile "meetgreet.flv"
```
