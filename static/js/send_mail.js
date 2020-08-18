async function send_mail(text, to_user) {
    let info = {
        'text': text,
        'to_user': to_user,
    };
    let response = await fetch('/camagru/comment_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    let result = await response.text();
}
