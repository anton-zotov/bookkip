<link href="/css/home/support.css" rel="stylesheet">

<table>
	<tr><td id="td1">
		<h1>Support</h1>
		<p>Something isn't working? Have any ideas, suggestions?</p>
	</td></tr><tr><td id="td2">
		<div id="chat-window">
			<? foreach ($messages as $message): ?>
				<div class="message-holder <?= $message->id_user_from == $id ? 'from' : 'to'; ?>">
					<div class="message"><?= $message->text; ?>
						<i><?= Common::RuDate($message->date); ?></i>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</td></tr><tr><td id="td3">
		<textarea></textarea>
		<div class="button-holder">
			<span class="hint">Ctrl + Enter </span>
			<button type="button" id="sent-button" class="btn btn-blue">Send</button>
		</div>
	</td></tr>
</table>

<script src="/js/home/support.js"></script>