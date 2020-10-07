<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<? if (!empty($arResult['FIRMS'])): ?>
	<? foreach ($arResult['FIRMS'] as $firm): ?>
		<ul>
			<li><b><?= $firm['NAME'] ?></b></li>
			<ul>
				<? foreach ($firm['ITEMS'] as $item): ?>
					<li><?= $item['NAME'] ?> - <?= $item['PRICE'] ?> - <?= $item['MATERIAL'] ?> - <?= $item['ARTNUMBER'] ?> - <?= $item['DETAIL_PAGE_URL'] ?></li>
				<? endforeach; ?>
			</ul>
		</ul>
	<? endforeach; ?>
<? endif; ?>
