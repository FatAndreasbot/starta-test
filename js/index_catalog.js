/**
 * 
 * @param {Event} e 
 */
function UpdatePriceRange(){
    const handler = document.getElementById("price-range");
    let value = handler.value;
    
    const label = document.getElementById("current-maximum");
    label.innerText = value;
}

window.onload = function(){
    document.getElementById("price-range").addEventListener("input", UpdatePriceRange);
    UpdatePriceRange()
}