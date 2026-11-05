import './bootstrap';
import { bootPublicSite } from './public/boot';
import { startAlpine } from './public/alpine';
import { registerHomeHeroSlideshow } from './public/home-hero';

bootPublicSite();
registerHomeHeroSlideshow();
startAlpine();
