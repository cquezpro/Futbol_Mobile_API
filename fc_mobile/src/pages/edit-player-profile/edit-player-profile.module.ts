import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { EditPlayerProfilePage } from "./edit-player-profile";
import { ComponentsModule } from "../../components/components.module";

@NgModule({
  declarations: [EditPlayerProfilePage],
  imports: [ComponentsModule, IonicPageModule.forChild(EditPlayerProfilePage)]
})
export class EditPlayerProfilePageModule {}
