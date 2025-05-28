// Gabimet ruhen globalisht
const profile_errors = {
  username: null,
  displayed_username: false,
  email: null,
  displayed_email: false,
};

async function change_data(event) {
  event.preventDefault(); // ndalon rifreskimin e faqes

  const email_div = document.getElementById("email");
  const username_div = document.getElementById("username");

  let changed_email = email_div.value.trim();
  let changed_username = username_div.value.trim();

  let count = check_info_errors(
    changed_email,
    email_div,
    changed_username,
    username_div
  );

  if (count !== 0) {
    display_profile_errors(email_div.parentElement, username_div.parentElement);
  } else {
    clear_profile_error(email_div.parentElement, username_div.parentElement);

    let data = {
      email: changed_email,
      username: changed_username,
    };

    const json = await send_request(
      data,
      "update_profile",
      "../server/control/updateProfil.control.php"
    );

    if ("success" in json) {
      create_notification("Data updated successfully!");
      goTo("/imperium/public/profile");
    } else {
      profile_server_errors(
        json,
        email_div.parentElement,
        username_div.parentElement
      );
    }
  }
}

function check_info_errors(email, email_div, username, username_div) {
  let count = 0;
  if (!email) {
    profile_errors.email = "Email can't be empty!";
    count++;
  } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
    profile_errors.email = "Invalid email!";
    count++;
  } else {
    profile_errors.email = null;
    clear_profile_error(email_div.parentElement, username_div.parentElement);
  }

  if (!username) {
    profile_errors.username = "Username can't be empty!";
    count++;
  } else {
    profile_errors.username = null;
    clear_profile_error(username_div.parentElement, username_div.parentElement);
  }

  return count;
}

function display_profile_errors(email_div, username_div) {
  if (profile_errors.email != null && !profile_errors.displayed_email) {
    create_error_message(profile_errors.email, email_div);
    profile_errors.displayed_email = true;
  }
  if (profile_errors.username != null && !profile_errors.displayed_username) {
    create_error_message(profile_errors.username, username_div);
    profile_errors.displayed_username = true;
  }
}

function clear_profile_error(email_div, username_div) {
  if (
    profile_errors.displayed_email &&
    email_div.nextElementSibling &&
    email_div.nextElementSibling.classList.contains("error")
  ) {
    email_div.nextElementSibling.remove();
    profile_errors.displayed_email = false;
  }
  if (
    profile_errors.displayed_username &&
    username_div.nextElementSibling &&
    username_div.nextElementSibling.classList.contains("error")
  ) {
    username_div.nextElementSibling.remove();
    profile_errors.displayed_username = false;
  }
}

function profile_server_errors(errors, email_div, username_div) {
  if ("email" in errors) {
    profile_errors.email = errors.email;
  }
  if ("username" in errors) {
    profile_errors.username = errors.username;
  }

  display_profile_errors(email_div, username_div);
}

async function logout_user() {
  const json = await send_request(
    {}, // No body data needed
    "logout", // Custom request type
    "../server/control/updateProfil.control.php"
  );

  if (json?.success) {
    create_notification("Logged out successfully!");
    goTo("/imperium/public/profile");
  } else {
    create_notification(json?.error || "Failed to log out.");
  }
}

function confirm_delete() {
  const confirmed = confirm(
    "Are you sure you want to delete your account? This action cannot be undone."
  );
  if (confirmed) {
    delete_account();
  }
}

async function delete_account() {
  const json = await send_request(
    {},
    "delete_account",
    "../server/control/updateProfil.control.php"
  );

  if (json?.success) {
    create_notification("Account deleted successfully!");
    goTo("/imperium/public/profile");
  } else {
    create_notification(json?.error || "Failed to delete.");
  }
}
async function remove_saved_item(productId) {
  const json = await send_request(
    { productId },
    "remove_item",
    "../server/control/updateProfil.control.php"
  );

  if (json?.success) {
    create_notification("Removed successfully!");
    goTo("/imperium/public/profile");
  } else {
    create_notification(json?.error || "Failed to remove.");
  }
}
async function removeFromCart(itemId) {
  const json = await send_request(
    { itemId },
    "remove_item",
    "../server/control/updateCart.control.php"
  );

  if (json?.success) {
    create_notification("Removed successfully!");
    goTo("/imperium/public/cart");
  } else {
    create_notification(json?.error || "Failed to remove.");
  }
}
async function add_saved_items_to_cart(productId) {
  const json = await send_request(
    { productId },
    "add_product",
    "../server/control/updateCart.control.php"
  );

  if (json?.success) {
    create_notification("Added successfully!");
    goTo("/imperium/public/profile");
  } else {
    create_notification(json?.error || "Failed to add.");
  }
}
