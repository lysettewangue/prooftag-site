import './bootstrap';
import { bootPublicSite } from './public/boot';
import { startAlpine } from './public/alpine';
import { registerHomeHeroSlideshow } from './public/home-hero';
import { enhanceHomeCentres } from './public/home-centres';
import { enhanceContactPage } from './public/contact-page';

bootPublicSite();
registerHomeHeroSlideshow();
enhanceHomeCentres();
enhanceContactPage();
startAlpine();
