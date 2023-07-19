const meganavBackground = document.getElementById('megabackground');
const primaryMenu = document.getElementById('menu-primary-menu');
const meganavTitle = document.getElementById('megabackground-title');

let currentNavHover = null;

for(const child of primaryMenu.children) {
    child.addEventListener("mouseover", () => {
        meganavBackground.style.display = 'block';
        curentNavHover = child;

        //Name of the current nav <li> item you're hovering over.
        let activeNavName = child.firstChild.innerText;

        // Check to see if the child element <li> has more than one child, 
        //if it does has a submenu which needs a title appended
        if(child.children.length > 1) {
            addTitle(child, activeNavName);
        }


    })

    child.addEventListener('mouseout', () => {
        meganavBackground.style.display = 'none';
        let activeNav = document.getElementById('active-nav')
        child.removeChild(activeNav);
    }
    )
}

//Creates a Header element and appends it to an element
function addTitle(elementToAppend, titleToAppend) {
    let activeNavTitle = document.createElement('h1');
    activeNavTitle.innerText = titleToAppend;
    activeNavTitle.id = 'active-nav';
    elementToAppend.appendChild(activeNavTitle);
}