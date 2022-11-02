import "./bootstrap";
import "./DefinedTerm.js";

import Alpine from "alpinejs";
import "wicg-inert";

import confirmPassword from "./confirmPassword.js";
import dateInput from "./dateInput.js";
import enhancedCheckboxes from "./enhancedCheckboxes.js";
import modal from "./modal.js";

window.Alpine = Alpine;
Alpine.data("confirmPassword", confirmPassword);
Alpine.data("dateInput", dateInput);
Alpine.data("enhancedCheckboxes", enhancedCheckboxes);
Alpine.data("modal", modal);
Alpine.start();
