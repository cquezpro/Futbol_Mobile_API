import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { ProfileInitialPage } from './profile-initial';
import { TranslateModule } from '@ngx-translate/core'
@NgModule({
  declarations: [
    ProfileInitialPage,
  ],
  imports: [
    IonicPageModule.forChild(ProfileInitialPage),
    TranslateModule
  ],
})
export class ProfileInitialPageModule {}
