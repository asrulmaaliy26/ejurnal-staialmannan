{**
 * templates/frontend/pages/indexSite.tpl
 *
 * Copyright (c) 2014-2024 Simon Fraser University
 * Copyright (c) 2003-2024 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Modernized Site index for STAI Al Mannan E-Journal Portal.
 * Menampilkan identitas lengkap dan utuh dari setiap jurnal tanpa terpotong.
 *}
{include file="frontend/components/header.tpl"}

<div class="page_index_site portal_container">

	{** 1. HERO SECTION PORTAL STAI AL MANNAN **}
	<div class="portal_hero">
		<div class="portal_hero_content">
			<div class="portal_hero_badge">
				<span class="badge_dot"></span> SISTEM INFORMASI JURNAL ILMIAH
			</div>
			<h1 class="portal_hero_title">
				Portal E-Jurnal <span>STAI Al Mannan</span>
			</h1>
			<p class="portal_hero_desc">
				Pusat publikasi karya ilmiah, hasil riset akademik, dan pengabdian masyarakat yang dikelola secara profesional oleh sivitas akademika Sekolah Tinggi Agama Islam Al Mannan Mojokerto.
			</p>
			<div class="portal_hero_stats">
				<div class="stat_card">
					<span class="stat_val">{$journals|@count}</span>
					<span class="stat_lbl">Jurnal Ilmiah</span>
				</div>
				<div class="stat_card">
					<span class="stat_val">OAI-PMH</span>
					<span class="stat_lbl">Open Access</span>
				</div>
				<div class="stat_card">
					<span class="stat_val">Peer-Reviewed</span>
					<span class="stat_lbl">Double Blind Review</span>
				</div>
				<div class="stat_card">
					<span class="stat_val">Online</span>
					<span class="stat_lbl">Full Electronic Submission</span>
				</div>
			</div>
		</div>
	</div>

	{if $highlights->count()}
		<div class="portal_highlights_wrap">
			{include file="frontend/components/highlights.tpl" highlights=$highlights}
		</div>
	{/if}

	{if $about}
		<div class="portal_about_card">
			<h2 class="section_subtitle">Tentang Portal</h2>
			<div class="about_content">
				{$about}
			</div>
		</div>
	{/if}

	{include file="frontend/objects/announcements_list.tpl" numAnnouncements=$numAnnouncementsHomepage}

	{** 2. DAFTAR JURNAL LENGKAP & UTUH (FULL SHOWCASE CARD) **}
	<div class="portal_journals_section">
		<div class="portal_section_header">
			<div>
				<h2 class="portal_section_title">Daftar Jurnal Ilmiah</h2>
				<p class="portal_section_subtitle">Profil lengkap dan informasi seluruh jurnal terbitan STAI Al Mannan</p>
			</div>
			<span class="journals_counter_badge">{$journals|@count} Jurnal Aktif</span>
		</div>

		{if !$journals|@count}
			<div class="portal_empty_alert">
				{translate key="site.noJournals"}
			</div>
		{else}
			<div class="portal_journals_list">
				{foreach from=$journals item=journal}
					{capture assign="url"}{url journal=$journal->getPath()}{/capture}
					{assign var="thumb" value=$journal->getLocalizedData('journalThumbnail')}
					{if !$thumb}
						{assign var="thumb" value=$journal->getData('journalThumbnail', 'id')}
					{/if}
					{if !$thumb}
						{assign var="thumb" value=$journal->getData('journalThumbnail', 'en')}
					{/if}
					{assign var="description" value=$journal->getLocalizedDescription()}
					{assign var="journalName" value=$journal->getLocalizedName()}

					<div class="journal_showcase_card">
						{** KOLOM KIRI: COVER BUKU UTUH (NO CROPPING) **}
						<div class="journal_showcase_media">
							{if $thumb}
								<a href="{$url}" class="journal_cover_link" title="Kunjungi {$journalName|escape}">
									<div class="journal_book_frame">
										<img class="journal_cover_img" 
										     src="{$journalFilesPath}{$journal->getId()}/{$thumb.uploadName|escape:"url"}" 
										     alt="{$thumb.altText|escape|default:$journalName}">
										<div class="journal_book_spine"></div>
									</div>
								</a>
							{else}
								<a href="{$url}" class="journal_cover_placeholder" title="Kunjungi {$journalName|escape}">
									<div class="placeholder_book">
										<span class="placeholder_icon">📚</span>
										<span class="placeholder_name">{$journalName|truncate:36:"..."}</span>
										<span class="placeholder_inst">STAI AL MANNAN</span>
									</div>
								</a>
							{/if}
						</div>

						{** KOLOM KANAN: IDENTITAS & DESKRIPSI LENGKAP **}
						<div class="journal_showcase_body">
							<div class="journal_meta_top">
								<span class="badge_tag badge_open_access">Open Access</span>
								<span class="badge_tag badge_peer_review">Peer-Reviewed</span>
								<span class="badge_tag badge_path">/{$journal->getPath()}</span>
							</div>

							<h3 class="journal_showcase_title">
								<a href="{$url}">
									{$journalName|escape}
								</a>
							</h3>

							{** DESKRIPSI LENGKAP (TETAP UTUH, DENGAN TABEL DAN TEKS PENUH) **}
							{if $description}
								{assign var="cleanHtml" value=$description|replace:"Â":""|replace:"Ã‚":""}
								<div class="journal_full_description">
									{$cleanHtml}
								</div>
							{/if}

							{** TOMBOL AKSI **}
							<div class="journal_showcase_footer">
								<a href="{$url}" class="btn_portal_primary">
									<span>Kunjungi Jurnal</span>
									<svg class="btn_icon" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
										<path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
									</svg>
								</a>
								<a href="{url journal=$journal->getPath() page="issue" op="current"}" class="btn_portal_secondary">
									Terbitan Terkini
								</a>
								<a href="{url journal=$journal->getPath() page="issue" op="archive"}" class="btn_portal_outline">
									Arsip Jurnal
								</a>
							</div>
						</div>
					</div>
				{/foreach}
			</div>
		{/if}
	</div>

</div><!-- .page_index_site -->

{** STYLESHEET PORTAL LENGKAP & RESPONSIF **}
<style>
/* Base Container */
.portal_container {
	max-width: 1200px;
	margin: 0 auto;
	padding: 1.5rem 1rem 3.5rem;
	font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
	color: #1e293b;
}

/* Hero Section */
.portal_hero {
	background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f766e 100%);
	border-radius: 20px;
	padding: 3rem 2.5rem;
	color: #ffffff;
	margin-bottom: 2.5rem;
	box-shadow: 0 10px 30px rgba(6, 78, 59, 0.18);
	position: relative;
	overflow: hidden;
}
.portal_hero::after {
	content: "";
	position: absolute;
	top: -50%;
	right: -10%;
	width: 400px;
	height: 400px;
	background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
	pointer-events: none;
}
.portal_hero_badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	background: rgba(255, 255, 255, 0.15);
	backdrop-filter: blur(8px);
	padding: 6px 14px;
	border-radius: 9999px;
	font-size: 0.75rem;
	font-weight: 700;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	margin-bottom: 1.25rem;
	border: 1px solid rgba(255, 255, 255, 0.2);
}
.badge_dot {
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background-color: #34d399;
	display: inline-block;
}
.portal_hero_title {
	font-size: 2.35rem;
	font-weight: 800;
	line-height: 1.2;
	margin: 0 0 1rem 0;
	color: #ffffff;
}
.portal_hero_title span {
	color: #a7f3d0;
}
.portal_hero_desc {
	font-size: 1.05rem;
	line-height: 1.65;
	max-width: 780px;
	margin: 0 0 2rem 0;
	color: #e6fffa;
	opacity: 0.95;
}
.portal_hero_stats {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
	gap: 1rem;
	border-top: 1px solid rgba(255, 255, 255, 0.18);
	padding-top: 1.5rem;
}
.stat_card {
	display: flex;
	flex-direction: column;
}
.stat_val {
	font-size: 1.35rem;
	font-weight: 800;
	color: #ffffff;
}
.stat_lbl {
	font-size: 0.8rem;
	color: #d1fae5;
	text-transform: uppercase;
	letter-spacing: 0.04em;
}

/* Section Header */
.portal_section_header {
	display: flex;
	justify-content: space-between;
	align-items: flex-end;
	margin-bottom: 2rem;
	padding-bottom: 0.75rem;
	border-bottom: 2px solid #e2e8f0;
}
.portal_section_title {
	font-size: 1.65rem;
	font-weight: 800;
	color: #0f172a;
	margin: 0 0 0.25rem 0;
}
.portal_section_subtitle {
	font-size: 0.92rem;
	color: #64748b;
	margin: 0;
}
.journals_counter_badge {
	background: #ecfdf5;
	color: #047857;
	font-weight: 700;
	font-size: 0.85rem;
	padding: 4px 14px;
	border-radius: 9999px;
	border: 1px solid #a7f3d0;
	white-space: nowrap;
}

/* List of Journal Showcase Cards */
.portal_journals_list {
	display: flex;
	flex-direction: column;
	gap: 2.25rem;
}

.journal_showcase_card {
	background: #ffffff;
	border-radius: 18px;
	border: 1px solid #e2e8f0;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	padding: 2rem;
	gap: 2rem;
	transition: all 0.28s ease;
}
.journal_showcase_card:hover {
	border-color: #34d399;
	box-shadow: 0 10px 30px rgba(15, 118, 110, 0.1);
}

/* Kolom Kiri: Cover Buku Utuh (Natural Aspect Ratio) */
.journal_showcase_media {
	width: 200px;
	min-width: 200px;
	display: flex;
	justify-content: center;
	align-items: flex-start;
	padding-top: 0.25rem;
}
.journal_cover_link {
	display: inline-block;
	text-decoration: none;
	line-height: 0;
}
.journal_book_frame {
	position: relative;
	display: inline-block;
	border-radius: 4px 8px 8px 4px;
	overflow: hidden;
	box-shadow: 
		-2px 0 6px rgba(0, 0, 0, 0.06),
		6px 12px 24px rgba(15, 23, 42, 0.18),
		0 2px 4px rgba(0, 0, 0, 0.08);
	background: #ffffff;
	transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.28s ease;
}
.journal_showcase_card:hover .journal_book_frame {
	transform: translateY(-4px) scale(1.03);
	box-shadow: 
		-2px 0 8px rgba(0, 0, 0, 0.08),
		8px 18px 30px rgba(15, 118, 110, 0.25),
		0 4px 8px rgba(0, 0, 0, 0.12);
}
.journal_book_spine {
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	width: 5px;
	background: linear-gradient(90deg, rgba(0, 0, 0, 0.25) 0%, rgba(255, 255, 255, 0.35) 50%, rgba(0, 0, 0, 0.1) 100%);
	pointer-events: none;
}
.journal_cover_img {
	display: block;
	max-width: 190px;
	max-height: 280px;
	width: auto;
	height: auto;
	object-fit: contain;
	border-radius: 4px 8px 8px 4px;
}

/* Placeholder Sampul Buku jika belum ada gambar */
.journal_cover_placeholder {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 160px;
	height: 230px;
	background: linear-gradient(145deg, #064e3b 0%, #047857 60%, #0f766e 100%);
	border-radius: 4px 8px 8px 4px;
	text-decoration: none;
	padding: 1.25rem 1rem;
	text-align: center;
	box-shadow: 6px 12px 24px rgba(15, 23, 42, 0.18);
	position: relative;
	transition: transform 0.28s ease;
}
.journal_cover_placeholder::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	bottom: 0;
	width: 6px;
	background: linear-gradient(90deg, rgba(0, 0, 0, 0.3) 0%, rgba(255, 255, 255, 0.4) 50%, rgba(0, 0, 0, 0.1) 100%);
}
.journal_showcase_card:hover .journal_cover_placeholder {
	transform: translateY(-4px) scale(1.03);
}
.placeholder_book {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
}
.placeholder_icon {
	font-size: 2.2rem;
	line-height: 1;
}
.placeholder_name {
	font-size: 0.82rem;
	font-weight: 700;
	color: #ffffff;
	line-height: 1.3;
}
.placeholder_inst {
	font-size: 0.62rem;
	font-weight: 800;
	letter-spacing: 0.06em;
	color: #a7f3d0;
	margin-top: 0.5rem;
	border-top: 1px solid rgba(255,255,255,0.2);
	padding-top: 0.4rem;
	display: block;
}

/* Kolom Kanan: Body & Konten Lengkap */
.journal_showcase_body {
	flex: 1;
	min-width: 0;
	display: flex;
	flex-direction: column;
}
.journal_meta_top {
	display: flex;
	gap: 8px;
	margin-bottom: 0.5rem;
	flex-wrap: wrap;
}
.badge_tag {
	font-size: 0.72rem;
	font-weight: 700;
	padding: 3px 10px;
	border-radius: 6px;
	text-transform: uppercase;
	letter-spacing: 0.04em;
}
.badge_open_access {
	background: #ecfdf5;
	color: #065f46;
	border: 1px solid #a7f3d0;
}
.badge_peer_review {
	background: #eff6ff;
	color: #1e40af;
	border: 1px solid #bfdbfe;
}
.badge_path {
	background: #f1f5f9;
	color: #475569;
}

.journal_showcase_title {
	margin: 0 0 0.85rem 0;
	font-size: 1.35rem;
	font-weight: 800;
	line-height: 1.35;
}
.journal_showcase_title a {
	color: #0369a1;
	text-decoration: none;
	transition: color 0.2s ease;
}
.journal_showcase_title a:hover {
	color: #0284c7;
	text-decoration: underline;
}

/* Deskripsi Lengkap & Normalisasi Tabel agar Rapi & Responsif */
.journal_full_description {
	color: #334155;
	font-size: 0.92rem;
	line-height: 1.65;
	margin-top: 0.5rem;
	margin-bottom: 1.5rem;
}
.journal_full_description table {
	width: 100% !important;
	max-width: 100% !important;
	height: auto !important;
	border-collapse: collapse !important;
	margin: 0.75rem 0 1.25rem 0 !important;
	border: none !important;
}
.journal_full_description thead {
	display: none !important;
}
.journal_full_description colgroup, 
.journal_full_description col {
	width: auto !important;
}
.journal_full_description tr {
	height: auto !important;
	border-bottom: 1px solid #f1f5f9 !important;
}
.journal_full_description tr:last-child {
	border-bottom: none !important;
}
.journal_full_description td, 
.journal_full_description th {
	padding: 6px 14px 6px 0 !important;
	vertical-align: top !important;
	font-size: 0.9rem !important;
	line-height: 1.5 !important;
	height: auto !important;
	border: none !important;
	background: transparent !important;
}
.journal_full_description td:first-child,
.journal_full_description th:first-child {
	width: 26% !important;
	min-width: 140px !important;
	max-width: 220px !important;
	color: #0f172a !important;
	font-weight: 700 !important;
}
.journal_full_description td:last-child,
.journal_full_description th:last-child {
	width: 74% !important;
	color: #334155 !important;
}
.journal_full_description p {
	margin: 0.6rem 0 !important;
	line-height: 1.65 !important;
}
.journal_full_description p:empty {
	display: none !important;
}
.journal_full_description em, .journal_full_description i {
	color: #475569;
	font-style: italic;
}
/* Bersihkan wrapper ChatGPT/artefak token */
.journal_full_description section[class*="text-token"] {
	all: unset !important;
	display: block !important;
}

/* Footer / Tombol Aksi */
.journal_showcase_footer {
	display: flex;
	gap: 0.75rem;
	align-items: center;
	margin-top: auto;
	padding-top: 1rem;
	border-top: 1px solid #f1f5f9;
	flex-wrap: wrap;
}
.btn_portal_primary {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	background: linear-gradient(135deg, #059669 0%, #047857 100%);
	color: #ffffff !important;
	padding: 9px 18px;
	border-radius: 8px;
	font-size: 0.88rem;
	font-weight: 600;
	text-decoration: none;
	box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
	transition: all 0.2s ease;
}
.btn_portal_primary:hover {
	background: linear-gradient(135deg, #047857 0%, #065f46 100%);
	box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
	transform: translateY(-1px);
}
.btn_portal_secondary {
	display: inline-flex;
	align-items: center;
	background: #f8fafc;
	color: #334155 !important;
	padding: 9px 16px;
	border-radius: 8px;
	font-size: 0.88rem;
	font-weight: 600;
	text-decoration: none;
	border: 1px solid #cbd5e1;
	transition: all 0.2s ease;
}
.btn_portal_secondary:hover {
	background: #e2e8f0;
	color: #0f172a !important;
}
.btn_portal_outline {
	display: inline-flex;
	align-items: center;
	background: transparent;
	color: #64748b !important;
	padding: 9px 14px;
	border-radius: 8px;
	font-size: 0.88rem;
	font-weight: 600;
	text-decoration: none;
	border: 1px dashed #cbd5e1;
	transition: all 0.2s ease;
}
.btn_portal_outline:hover {
	background: #f1f5f9;
	color: #0f172a !important;
	border-style: solid;
}

/* Responsivitas Layar */
@media (max-width: 768px) {
	.journal_showcase_card {
		flex-direction: column;
		align-items: center;
		padding: 1.5rem;
		gap: 1.5rem;
	}
	.journal_showcase_media {
		width: 100%;
		justify-content: center;
	}
	.journal_full_description table td:first-child,
	.journal_full_description table th:first-child {
		width: 35% !important;
		min-width: 110px !important;
	}
	.portal_hero {
		padding: 2rem 1.5rem;
	}
	.portal_hero_title {
		font-size: 1.75rem;
	}
}
</style>

{include file="frontend/components/footer.tpl"}
