import { NgModule } from "@angular/core";
import { IonicPageModule } from "ionic-angular";
import { ModalCountrySearchPage } from "./modal-country-search";
import { ComponentsModule } from "../../components/components.module";

@NgModule({
  declarations: [ModalCountrySearchPage],
  imports: [ComponentsModule, IonicPageModule.forChild(ModalCountrySearchPage)]
})
export class ModalCountrySearchPageModule {}
