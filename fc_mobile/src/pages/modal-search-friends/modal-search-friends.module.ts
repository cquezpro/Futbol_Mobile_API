import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { ModalSearchFriendsPage } from "./modal-search-friends";
import { ComponentsModule } from "../../components/components.module";

@NgModule({
  declarations: [ModalSearchFriendsPage],
  imports: [ComponentsModule, IonicPageModule.forChild(ModalSearchFriendsPage)]
})
export class ModalSearchFriendsPageModule {}
