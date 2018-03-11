import { NgModule } from '@angular/core';
import {
    IconHome, IconHeart, IconShoppingCart, IconUsers, IconBarChart2, IconLayers,
    IconPlusCircle, IconFileText, IconTag, IconMonitor
} from 'angular-feather';

const icons = [
    IconHome,
    IconHeart,
    IconShoppingCart,
    IconUsers,
    IconBarChart2,
    IconLayers,
    IconPlusCircle,
    IconFileText,
    IconTag,
    IconMonitor
];

@NgModule({
  exports: icons
})
export class IconsModule { }
