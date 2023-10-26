import "./bootstrap";

import { Livewire, Alpine } from "../../vendor/livewire/livewire/dist/livewire.esm";
import "wicg-inert";

import confirmPassword from "./confirmPassword.js";
import datePicker from "./datePicker.js";
import enhancedCheckboxes from "./enhancedCheckboxes.js";
import modal from "./modal.js";
import "./vimeoPlayer.js";

Alpine.data("confirmPassword", confirmPassword);
Alpine.data("datePicker", datePicker);
Alpine.data("enhancedCheckboxes", enhancedCheckboxes);
Alpine.data("modal", modal);
Livewire.start();
