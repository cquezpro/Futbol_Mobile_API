import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  ViewController
} from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";

@IonicPage()
@Component({
  selector: "page-modal-futbol-types",
  templateUrl: "modal-futbol-types.html"
})
export class ModalFutbolTypesPage {
  fut11: any;
  futTypesSel: any;
  ftTypes: any[];
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public viewCtrl: ViewController,
    public utils: UtilsProvider
  ) {}

  ionViewDidLoad() {
    let url = `resources/list-futbol-types`;
    this.utils.rest(
      url,
      "get",
      true,
      {},
      resp => {
        this.ftTypes = resp.futbol_types;
      },
      err => {}
    );
  }

  onChangeToggle(item) {
    console.log(item);
  }

  dismiss() {
    this.viewCtrl.dismiss();
  }

  selectFut() {
    let items = this.ftTypes.filter(function(item) {
      return item.selected == true;
    });
    this.viewCtrl.dismiss(items);
  }
}
