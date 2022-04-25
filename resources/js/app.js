require("./bootstrap");
require("./DefinedTerm.js");

import Alpine from "alpinejs";
import autocomplete from "./autocomplete.js";
import confirmPassword from "./confirmPassword.js";
import dateInput from "./dateInput.js";
import enhancedCheckboxes from "./enhancedCheckboxes.js";

window.Alpine = Alpine;
Alpine.data("autocomplete", autocomplete);
Alpine.data("confirmPassword", confirmPassword);
Alpine.data("dateInput", dateInput);
Alpine.data("enhancedCheckboxes", enhancedCheckboxes);
Alpine.start();

