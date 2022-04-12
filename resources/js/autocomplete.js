import accessibleAutocomplete from "accessible-autocomplete";

export default () => ({
    init() {
        accessibleAutocomplete.enhanceSelectElement({
            defaultValue: "",
            displayMenu: "overlay",
            selectElement: this.$root,
            showAllValues: true
        });
    }
});
