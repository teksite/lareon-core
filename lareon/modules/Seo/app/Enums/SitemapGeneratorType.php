<?php

namespace Lareon\Modules\Seo\App\Enums;

enum SitemapGeneratorType: string
{
   case DB= 'database';
   case Crawler= 'crawler';

}
