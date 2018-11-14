import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { ModalFutbolTypesPage } from "./modal-futbol-types";
import { ComponentsModule } from "../../components/components.module";

@NgModule({
  declarations: [ModalFutbolTypesPage],
  imports: [ComponentsModule, IonicPageModule.forChild(ModalFutbolTypesPage)]
})
export class ModalFutbolTypesPageModule {}
