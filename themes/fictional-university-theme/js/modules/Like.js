import $ from 'jquery';

class Like {
    constructor() {
        this.events();
    }

    // Events
    events() {
        $( ".like-box" ).on( "click", this.ourClickDispatcher.bind( this ) );
    }

    // Methods
    ourClickDispatcher(e) {
        let currentLikeBox = $( e.target ).closest( ".like-box" );

        if( currentLikeBox.attr( 'data-exists' ) == 'yes' ) {
            this.deleteLike( currentLikeBox );
        } else {
            this.createLike( currentLikeBox );
        }
    }

    createLike( currentLikeBox ) {
        $.ajax({
            beforeSend: ( xhr ) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce ); // WordPress will be using the exact X-WP-Nonce
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST',
            data: {'professorId': currentLikeBox.data( 'professor' )}, // same as: /wp-json/university/v1/manageLike?professorId=xxx, in the url attribute above
            success: ( response ) => {
                currentLikeBox.attr( 'data-exists', 'yes' );
                let likeCount = parseInt( currentLikeBox.find(".like-count").html(), 10 );
                likeCount++;
                currentLikeBox.find( ".like-count" ).html(likeCount);
                currentLikeBox.attr( "data-like", response ); // response contains the ID number of that new like post
                console.log( response );
            },
            error: ( response ) => {
                console.log( response );
            }
        });
    }

    deleteLike( currentLikeBox ) {
        $.ajax({
            beforeSend: ( xhr ) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); // WordPress will be using the exact X-WP-Nonce
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            data: {'like': currentLikeBox.attr('data-like')},
            type: 'DELETE',
            success: ( response ) => {
                currentLikeBox.attr('data-exists', 'no');
                let likeCount = parseInt( currentLikeBox.find(".like-count").html(), 10 );
                likeCount--;
                currentLikeBox.find( ".like-count" ).html( likeCount );
                currentLikeBox.attr( "data-like", '' ); // response contains the ID number of that new like post
                console.log( response );
            },
            error: ( response ) => {
                console.log( response );
            }
        });
    }

}

export default Like;



// JQUERY FREE VERSION BELOW
/*

import axios from "axios"

class Like {
  constructor() {
    if (document.querySelector(".like-box")) {
      axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce
      this.events()
    }
  }

  events() {
    document.querySelector(".like-box").addEventListener("click", e => this.ourClickDispatcher(e))
  }

  // methods
  ourClickDispatcher(e) {
    let currentLikeBox = e.target
    while (!currentLikeBox.classList.contains("like-box")) {
      currentLikeBox = currentLikeBox.parentElement
    }

    if (currentLikeBox.getAttribute("data-exists") == "yes") {
      this.deleteLike(currentLikeBox)
    } else {
      this.createLike(currentLikeBox)
    }
  }

  async createLike(currentLikeBox) {
    try {
      const response = await axios.post(universityData.root_url + "/wp-json/university/v1/manageLike", { "professorId": currentLikeBox.getAttribute("data-professor") })
      if (response.data != "Only logged in users can create a like.") {
        currentLikeBox.setAttribute("data-exists", "yes")
        var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10)
        likeCount++
        currentLikeBox.querySelector(".like-count").innerHTML = likeCount
        currentLikeBox.setAttribute("data-like", response.data)
      }
      console.log(response.data)
    } catch (e) {
      console.log("Sorry")
    }
  }

  async deleteLike(currentLikeBox) {
    try {
      const response = await axios({
        url: universityData.root_url + "/wp-json/university/v1/manageLike",
        method: 'delete',
        data: { "like": currentLikeBox.getAttribute("data-like") },
      })
      currentLikeBox.setAttribute("data-exists", "no")
      var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10)
      likeCount--
      currentLikeBox.querySelector(".like-count").innerHTML = likeCount
      currentLikeBox.setAttribute("data-like", "")
      console.log(response.data)
    } catch (e) {
      console.log(e)
    }
  }
}

export default Like

 */