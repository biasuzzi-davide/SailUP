<?php

require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

$html = buildPage('../pages/catalogo_noleggio.html', $_SERVER['PHP_SELF']);

$db = new DBConnection();
$prodottiNoleggio = $db->getProdottiWithMedia('Noleggio');

$cardsHtml = '';
if ($prodottiNoleggio && is_array($prodottiNoleggio) && count($prodottiNoleggio) > 0) {
		foreach ($prodottiNoleggio as $prodotto) {
				$idProdotto = $prodotto['IDProdotto'] ?? '';
				if ($idProdotto === '') {
						continue;
				}

				$imageUrl = $prodotto['URL_Media'] ?? '../img/placeholder.png';
				$altText = $prodotto['Testo_Alternativo'] ?? 'Immagine non disponibile';

				$productName = htmlspecialchars($prodotto['Nome_Prodotto'] ?? 'Prodotto', ENT_QUOTES);
				$description = htmlspecialchars($prodotto['Descrizione_Breve'] ?? 'Descrizione non disponibile.', ENT_QUOTES);

				$badgeTextRaw = $prodotto['Tipologia_Prodotto'] ?? 'Noleggio';
				$badgeText = htmlspecialchars($badgeTextRaw, ENT_QUOTES);
				$badgeKey = strtolower($badgeTextRaw);
				$badgeClass = 'badge-motore';
				if (strpos($badgeKey, 'vela') !== false) {
						$badgeClass = 'badge-vela';
				} elseif (strpos($badgeKey, 'gommone') !== false) {
						$badgeClass = 'badge-gommone';
				} elseif (strpos($badgeKey, 'motore') !== false) {
						$badgeClass = 'badge-motore';
				}

				$lengthValue = $prodotto['Lunghezza_Barca_Metri'];
				$formattedLength = '—';
				if ($lengthValue !== null && $lengthValue !== '') {
						$formattedLength = number_format((float) $lengthValue, 2, ',', '.');
						$formattedLength = rtrim(rtrim($formattedLength, '0'), ',');
						$formattedLength .= 'm';
				}

				$postiTotali = isset($prodotto['Posti_Totali']) ? (int) $prodotto['Posti_Totali'] : null;
				$postiDescrizione = $postiTotali !== null ? $postiTotali . ' posti' : '—';

				$richiedePatente = filter_var($prodotto['Richiede_Patente'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
				$richiedePatente = $richiedePatente ?? false;
				$patenteIcon = $richiedePatente ? '🎫' : '✅';
				$patenteLabel = $richiedePatente ? 'Patente Richiesta' : 'Patente non Richiesta';

				$prezzoBase = isset($prodotto['Prezzo_Base']) ? number_format((float) $prodotto['Prezzo_Base'], 0, ',', '.') : '—';

				$detailUrl = 'dettaglio_barca.php?id=' . rawurlencode($idProdotto);

				$cardsHtml .= '<article class="product-card">
						<img class="product-card-image" src="' . htmlspecialchars($imageUrl, ENT_QUOTES) . '" alt="' . htmlspecialchars($altText, ENT_QUOTES) . '" width="400" height="267" loading="lazy">
						<div class="product-card-content">
							<div class="product-header">
								<h3 class="product-title">
									' . $productName . '
								</h3>
								<span class="product-badge ' . $badgeClass . '">' . $badgeText . '</span>
							</div>

							<p class="product-description">
								' . $description . '
							</p>

							<ul class="product-specs">
								<li class="spec-item">
									<span class="spec-icon" aria-hidden="true">📏</span>
									' . $formattedLength . '
								</li>
								<li class="spec-item">
									<span class="spec-icon" aria-hidden="true">👥</span>
									' . $postiDescrizione . '
								</li>
								<li class="spec-item">
									<span class="spec-icon" aria-hidden="true">' . $patenteIcon . '</span>
									' . $patenteLabel . '
								</li>
							</ul>

							<div class="product-footer">
								<div class="product-price">
									<span class="price-label">da</span>
									<span class="price-value">' . $prezzoBase . '€</span>
									<span class="price-period">/giorno</span>
								</div>
								<a href="' . $detailUrl . '" class="product-cta" aria-label="Vedi dettagli ' . $productName . '">
									Vedi dettagli →
								</a>
							</div>
						</div>
					</article>';
		}
} else {
		$cardsHtml = '<p class="catalog-empty">Al momento non ci sono imbarcazioni disponibili in noleggio. Torna più tardi.</p>';
}

$html = str_replace('[NOLEGGIO_CARDS]', $cardsHtml, $html);

echo $html;
?>