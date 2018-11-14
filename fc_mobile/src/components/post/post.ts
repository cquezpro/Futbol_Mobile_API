import { Component, Input, Output, EventEmitter } from "@angular/core";
import { PopoverController } from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";
import { AuthProvider } from "../../providers/auth/auth";
import { PopoverButtonsComponent } from "../popover-buttons/popover-buttons";
/**
 * Generated class for the PostComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: "post",
  templateUrl: "post.html"
})
export class PostComponent {
  @Input()
  post: any = {};
  @Input()
  user: any = {};
  @Output()
  post_deleted = new EventEmitter<string>();
  options: any[] = [];
  isEditing: boolean = false;
  commentText: String;
  constructor(
    private utils: UtilsProvider,
    private auth: AuthProvider,
    private popoverCtrl: PopoverController
  ) {
    this.options = [
      {
        text: "Editar",
        event: () => {
          this.isEditing = true;
        }
      },
      {
        text: "Eliminar",
        event: () => {
          this.deletePost();
        }
      }
    ];
  }
  comment(comment) {
    this.utils.rest(
      "posts/" + this.post.id + "/comments",
      "post",
      false,
      { body: comment, user: this.auth.getCurrentUser().id },
      resp => {
        this.post.comments.unshift(resp);
        comment = "";
      },
      undefined
    );
  }
  like() {
    this.utils.rest(
      "posts/" + this.post.id + "/likes",
      "post",
      true,
      { user: this.auth.getCurrentUser().hash_id },
      resp => {
        this.post = resp;
      },
      undefined
    );
  }
  deletePost() {
    this.utils.dialogTrueFalse(
      "Eliminar post",
      "Desea eliminar este post?",
      () => {
        this.utils.rest(
          "posts/" + this.post.id + "/delete",
          "delete",
          true,
          undefined,
          resp => {
            if (this.post_deleted) {
              this.post_deleted.emit(this.post.id);
            }
          },
          undefined
        );
      }
    );
  }
  editPost() {
    if (this.post.body === "") {
      this.utils.toast("El post no debe quedar vacio");
    } else {
      this.utils.rest(
        "posts/" + this.post.id,
        "put",
        true,
        this.post,
        resp => {
          this.isEditing = false;
        },
        undefined
      );
    }
  }
  postOptions(evt) {
    this.popoverCtrl
      .create(PopoverButtonsComponent, this.options)
      .present({ ev: evt });
  }
  commentOptions(evt, comment, i) {
    let commentPopover = [
      {
        text: "Editar",
        event: () => {
          comment.isEditing = true;
          comment.save = () => {
            this.utils.rest(
              "comments/" + comment.id,
              "put",
              true,
              { body: comment.body },
              resp => {
                this.utils.toast("Comentario modificado");
                comment.isEditing = false;
              },
              undefined
            );
          };
        }
      },
      {
        text: "Eliminar",
        event: () => {
          this.utils.dialogTrueFalse(
            "Eliminar comentario",
            "Desea eliminar este comentario?",
            () => {
              this.utils.rest(
                "comments/" + comment.id + "",
                "delete",
                true,
                undefined,
                resp => {
                  this.post.comments.splice(i, 1);
                },
                undefined
              );
            }
          );
        }
      }
    ];
    this.popoverCtrl
      .create(PopoverButtonsComponent, commentPopover)
      .present({ ev: evt });
  }
}

/** post object
[
  {
    "body": null,
    "hash_id": "B7N2rO6bnyzPqyXgYEWo",
    "created_at": "Jul 16, 2018",
    "updated_at": "Jul 16, 2018",
    "created_at_diff": "1w",
    "updated_at_diff": "1w",
    "likes": {
      "total": 0,
      "iLiked": false
    },
    "images": [
      {
        "path": "https:\/\/pcfc.nyc3.digitaloceanspaces.com\/users_media_images_dev_api\/iamge_feeds\/QfNaz8F7vqS32wL7h29RewUUTPTwoMR3wtw57B0d.jpeg",
        "hash_id": "oOBLRXr2emQekVdA16Wm",
        "isCover": false,
        "isAvatar": false
      }
    ],
    "videos": [
      
    ],
    "user": {
      "first_name": "David",
      "last_name": "Mogollon",
      "email": "damofer2004@hotmail.com",
      "phone": "3003943926",
      "provider": null,
      "deleted_at": null,
      "city_id": 0,
      "full_name": "David Mogollon",
      "has_preferences": false,
      "hash_id": "qlbgwkdQnJgP9M6DvmLV",
      "avatar": null,
      "cover": null,
      "followers": {
        "total": 0,
        "data": [
          
        ]
      },
      "followings": {
        "total": 0,
        "data": [
          
        ]
      },
      "generalInformation": {
        
      },
      "technicalInformation": null,
      "representative": null,
      "speakLanguages": [
        
      ],
      "gamePositions": [
        
      ],
      "link": {
        "user_show": "http:\/\/staging.futbolconnect.com\/v1\/users\/qlbgwkdQnJgP9M6DvmLV"
      }
    },
    "comments": [
      {
        "body": "texto",
        "created_at": "Jul 25, 2018",
        "updated_at": "Jul 25, 2018",
        "created_at_diff": "1min",
        "updated_at_diff": "1min",
        "isOwner": true,
        "link_edit": "http:\/\/staging.futbolconnect.com\/v1\/comments\/48NWEbvKp6bpOQlDVw5G",
        "user": {
          "full_name": "lucho meza",
          "avatar": null,
          "links": {
            "show_user": "http:\/\/staging.futbolconnect.com\/v1\/users\/Qax6YjkwpQoPBRZ9LlVr"
          }
        }
      }
    ],
    "link": {
      "post": "http:\/\/staging.futbolconnect.com\/v1\/posts\/B7N2rO6bnyzPqyXgYEWo"
    }
  }
]
*/
