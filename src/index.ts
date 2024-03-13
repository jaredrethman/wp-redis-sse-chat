/**
 * Chat
 */

import "./index.css";

const { wrsChat } = window;
wrsChat.state = {
  chats: {}
};

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("wrs-chat-form");
  const userSelect = document.getElementById("wrs-chat-user-select");

  const myId = Number(wrsChat.userId);
  const chats = {};
  let receiverId = 0,
    title = "";

  userSelect.addEventListener("change", (event) => {
    receiverId = Number(event.target.value);
    title =
      receiverId < myId ? `${receiverId}-${myId}` : `${myId}-${receiverId}`;
      
      // chats[title] = {postId: undefined};
    console.log(title);
  });

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(form as HTMLFormElement);

    formData.append("action", "submit_wrs_chat");
    formData.append("nonce", wrsChat.nonce);
    formData.append("user_id", wrsChat.userId);
    formData.append("receiver_id", receiverId.toString());
    formData.append("wrs_chat_title", title);
    formData.append("post_id", chats[title] ?? 0 );

    fetch(wrsChat.ajaxUrl, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then(({ success, data }: { data: any; success: boolean }) => {
        if (success) {
          console.log("Chat post created with ID:", data.post_id);
          chats[title] = data.post_id;
        } else {
          console.error("Failed to create chat post:", data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  });
});
