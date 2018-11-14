import { NgModule } from '@angular/core';
import { PostComponent } from './post/post';
import { IonicModule } from 'ionic-angular';
import { UserHeaderComponent } from './user-header/user-header';
import { UserProgressComponent } from './user-progress/user-progress';
import { PostButtonComponent } from './post-button/post-button';
import { TitleComponent } from './title/title';
import { PartProgressComponent } from './part-progress/part-progress';
import { RoundProgressModule } from 'angular-svg-round-progressbar';


@NgModule({
	declarations: [PostComponent,
    UserHeaderComponent,
    UserProgressComponent,
    PostButtonComponent,
    TitleComponent,
    PartProgressComponent],
	imports: [IonicModule,RoundProgressModule],
	exports: [PostComponent,
    UserHeaderComponent,
    UserProgressComponent,
    PostButtonComponent,
    TitleComponent,
    PartProgressComponent]
})
export class ComponentsModule {}
