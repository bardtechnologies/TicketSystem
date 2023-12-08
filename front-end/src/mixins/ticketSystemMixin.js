import { Dark } from "quasar";

export function darkModeMixin() {
  return {
    setDarkMode(value) {
      Dark.set(value);
      localStorage.setItem("ticket_app.dark_mode_enabled", value);
    },
  };
}
