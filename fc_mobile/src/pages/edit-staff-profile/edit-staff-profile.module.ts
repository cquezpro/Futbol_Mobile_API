import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { EditStaffProfilePage } from "./edit-staff-profile";
import { ComponentsModule } from "../../components/components.module";

@NgModule({
  declarations: [EditStaffProfilePage],
  imports: [ComponentsModule, IonicPageModule.forChild(EditStaffProfilePage)]
})
export class EditStaffProfilePageModule {}
