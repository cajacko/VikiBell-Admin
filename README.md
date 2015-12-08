## Overview

A theme for Viki Bell

## wkhtmltopdf

Navigate to the folder with the application in.

wkhtmltopdf --dpi 600 --image-dpi 600 --margin-bottom 0mm --margin-left 0mm --margin-right 0mm --margin-top 0mm --page-height 250mm --page-width 200mm --no-outline --disable-javascript vikibell.local.com/?action=pdf vikibell.pdf


https://www.pdflabs.com/tools/pdftk-server/

Add pdfk and wkhtmltopdf to PATH


pdftk vikibell.pdf vikibell2.pdf cat output out.pdf