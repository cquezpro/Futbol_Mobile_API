import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { WallPage } from "./wall";

import { ComponentsModule } from "../../components/components.module";
import { TranslateModule } from "@ngx-translate/core";

@NgModule({
  declarations: [WallPage],
  imports: [
    ComponentsModule,
    TranslateModule.forChild(),
    IonicPageModule.forChild(WallPage)
  ]
})
export class WallPageModule {}
