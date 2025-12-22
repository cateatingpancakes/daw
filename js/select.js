function bindSelectData(selectId, textboxId, dataName) {
    const select = document.getElementById(selectId);
    const textbox = document.getElementById(textboxId);

    if(select !== null && textbox !== null) {
        let map = {};

        for(const option of select.children) {
            let key = option.attributes["value"].value;
            let value = option.attributes["data-" + dataName].value;
            map[key] = value;
        }

        select.addEventListener("change", function() {
            textbox.innerText = map[select.value];
        });

        textbox.innerText = map[Object.keys(map)[0]];
    }
}
