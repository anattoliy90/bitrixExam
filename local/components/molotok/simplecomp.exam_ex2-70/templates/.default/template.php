<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['NEWS'])):
	foreach ($arResult['NEWS'] as $news):
	$sections = implode(', ', $news['SECTIONS']);
	?>
		<ul>
			<li><b><?= $news['NAME']; ?></b> - <?= $news['ACTIVE_FROM']; ?> (<?= $sections;?>)</li>
			<ul>
				<? foreach ($news['ITEMS'] as $item): ?>
					<li><?= $item['NAME']; ?> - <?= $item['PROPERTY_PRICE_VALUE']; ?> - <?= $item['PROPERTY_MATERIAL_VALUE']; ?> - <?= $item['PROPERTY_ARTNUMBER_VALUE']; ?></li>
				<? endforeach; ?>
			</ul>
		</ul>
	<?
	endforeach;
endif;
