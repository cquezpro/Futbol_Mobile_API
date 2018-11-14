import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { PostCreatePage } from './post-create';
import { ComponentsModule } from '../../components/components.module';
@NgModule({
  declarations: [
    PostCreatePage,
  ],
  imports: [
  	ComponentsModule,
    IonicPageModule.forChild(PostCreatePage),
  ],
})
export class PostCreatePageModule {}
