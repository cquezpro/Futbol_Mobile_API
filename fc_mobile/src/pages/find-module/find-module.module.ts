import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { FindModulePage } from './find-module';

@NgModule({
  declarations: [
    FindModulePage,
  ],
  imports: [
    IonicPageModule.forChild(FindModulePage),
  ],
})
export class FindModulePageModule {}
