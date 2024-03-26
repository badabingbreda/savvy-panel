/**
 * code and css found here:
 * https://codepen.io/cleveryeti/pen/JjwNqgP
 */
/*
  How to use:
  
  create the notification object using new Notifications and pass it the notification wrapper element
  ex: const notis = new Notifications(document.querySelector(".notifications"))
  
  make notifications by using notis.create() (or *.create() if you names it something else)
  
  notis.create() parameters:
    Title: string,
    Description: string,
    Duration: seconds (default 2s, 0 makes it stay forever),
    Destroy on click: boolean
      (determines if the notification should disappear when clicked, default: false)
    Click function: function
      (gets called when the notification is clicked if it isnt undefined, default: undefined)
*/
class Notifications {
    constructor(el) {
      this.el = el;
    }
    
    // function to create new elements with a class (cleans up code)
    createDiv(className = "") {
      const el = document.createElement("div");
      el.classList.add(className);
      return el;
    }
    // function to add text nodes to elements
    addText(el, text) {
      el.appendChild(document.createTextNode(text));
    }
    
    create( 
      settings = {},
    ) {

        settings = {
            title : settings.title || 'Untitled notification',
            description : settings.description || "",
            duration : settings.duration || 2,
            destroyOnClick : settings.destroyOnClick || false,
            clickFunction : settings.clickFunction || undefined,
            status : settings.status || 'success',
        };
      
      // functions
        function destroy(animate) {
            if (animate) {
                notiEl.classList.add("out");
                notiEl.addEventListener("animationend", () => {notiEl.remove()});
            } else {
                notiEl.remove();
            }
        }
      
        // create the elements and add their content
        const notiEl = this.createDiv("noti");
        notiEl.dataset.status = settings.status;
        const notiCardEl = this.createDiv("noticard");
        const glowEl = this.createDiv("notiglow");
        const borderEl = this.createDiv("notiborderglow");
      
        const titleEl = this.createDiv("notititle");
        this.addText(titleEl, settings.title);
      
        const descriptionEl = this.createDiv("notidesc");
        this.addText(descriptionEl, settings.description);
      
        // append the elements to each other
        notiEl.appendChild(notiCardEl);
        notiCardEl.appendChild(glowEl);
        notiCardEl.appendChild(borderEl);
        notiCardEl.appendChild(titleEl);
        notiCardEl.appendChild(descriptionEl);
      
        this.el.appendChild(notiEl);
      
        // transition the height of the container to the height of the visible card
        //console.log("height", notiCardEl.scrollHeight);
        requestAnimationFrame(function() {
            notiEl.style.height = "calc(0.25rem + " + notiCardEl.getBoundingClientRect().height + "px)";
        });
  
        // hover animation
        notiEl.addEventListener("mousemove", (event) => {
            const rect = notiCardEl.getBoundingClientRect();
            const localX = (event.clientX - rect.left) / rect.width;
            const localY = (event.clientY - rect.top) / rect.height;
  
            // console.log(localX, localY);
            glowEl.style.left = localX * 100 + "%";
            glowEl.style.top = localY * 100 + "%";
  
            borderEl.style.left = localX * 100 + "%";
            borderEl.style.top = localY * 100 + "%";
        });
      
        // onclick function if one is set
        if (settings.clickFunction != undefined) {
            notiEl.addEventListener("click", settings.clickFunction );
        }
      
        // destroy the notification on click if it is set to do so
        if ( settings.destroyOnClick ) {
            notiEl.addEventListener("click", () => destroy(true));
        }
      
      
        // remove the notification after the set time if there is one
        if ( settings.duration != 0) {
            setTimeout(() => {
                notiEl.classList.add("out");
                notiEl.addEventListener("animationend", () => {notiEl.remove()});
            }, settings.duration * 1000);
        }
        return notiEl;
    }
}
  
notis = new Notifications(document.querySelector(".notifications"));