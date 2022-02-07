require("./bootstrap");

import Alpine from "alpinejs";

import confirmPassword from "./confirmPassword.js";
import dateInput from "./dateInput.js";

window.Alpine = Alpine;

Alpine.data("confirmPassword", confirmPassword);
Alpine.data("dateInput", dateInput);

Alpine.start();
