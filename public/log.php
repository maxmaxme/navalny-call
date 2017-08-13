<?php

require '../config.php';

$phones = $db->getAll("select 
	p.ID,
	p.Phone,
	p.UserID	
from phones p

order by p.ID DESC");


foreach ($phones as $item) {

	echo '<h3>Разговор с +7' . $item['Phone'] . ' от юзера ' . $item['UserID'] . '</h3>';

	$log = $db->getAll("select 
	l.DateTime,
	sb.Text as `From`,
	s.Text as `To`
from log l

inner join script_buttons sb on sb.ID=l.ButtonID
left join script s on s.ID=sb.ScriptID

where l.PhoneID=?i


order by l.DateTime", $item['ID']);


	echo '<hr>';

	echo '<div class="log-item">
		<div class="log-header">+7' . $item['Phone'] . '</div>
		<div class="log-content">';

			foreach ($log as $item_log) {

				if (!$item_log['To']) {
					$item_log['To'] = $item_log['From'];
					$item_log['From'] = '';
				}

				if ($item_log['To'])
					echo '<div class="log-message to"><div>' . $item_log['To'] . '</div></div>';
				if ($item_log['From'])
					echo '<div class="log-message from"><div>' . $item_log['From'] . '</div></div>';
			}


		echo '</div>
	</div>';

}

?>

<style>
	body {
		font-family: Helvetica;
	}
	.log-item {
		width: 400px;
		background: #dae0ec;
		padding-bottom: 5px;
	}
	.log-header {
		background: #889eb6;
		text-align: center;
		padding: 10px 0;
		color: white;
	}
	.log-content .log-message > div {
		padding: 10px;
		color: white;
		border-radius: 15px;
		margin: 5px;
		display: inline-block;
	}
	.log-content .log-message.to {
		text-align: right;
	}
	.log-content .log-message.from > div {
		background: #d1d1d1;
		border: 1px solid #898c93;
	}
	.log-content .log-message.to > div {
		background: #97d64a;
		border: 1px solid #898c93;
		text-align: right;
	}
</style>

