<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF - {{ $annee->libelle ?? 'N/A' }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* En-tête officiel */
        .official-header { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-left { width: 35%; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 10px; }
        .header-right { width: 35%; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 10px; }
        .header-center { width: 30%; text-align: center; }
        .motto { font-style: italic; font-size: 9px; margin-top: 5px; text-transform: none; }
        
        /* Titre Principal */
        .document-title { text-align: center; margin: 20px 0; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px 0; }
        .document-title h1 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .list-subtitle { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; }

        /* Infos contextuelles */
        .info-grid { width: 100%; margin-bottom: 15px; }
        .info-item { width: 50%; vertical-align: top; }
        .label { font-weight: bold; text-transform: uppercase; font-size: 10px; }

        /* Tableau */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #1e40af; color: white; border: 1px solid #1e3a8a; padding: 8px; text-transform: uppercase; font-size: 10px; }
        table.data-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        
        /* Pied de page */
        .footer { position: fixed; bottom: -1cm; left: 0; right: 0; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }


        .school-logo {
    width: 70px;
    height: auto;
    margin: 0 auto 5px auto;
    display: block;
}

    </style>
</head>
<body>

    <table class="official-header">
        <tr>
            <td class="header-left">
                RÉPUBLIQUE DU CAMEROUN<br>
                <span class="motto">Paix - Travail - Patrie</span><br>
                -------<br>
                MINISTÈRE DE L'ENSEIGNEMENT SUPÉRIEUR
            </td>
            <td class="header-center">
              <td class="header-center">
            <img
                src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('images/boristech.jpg'))) }}"
                class="school-logo"
                alt="Logo de l'école"
            >
        </td>
            </td>
            <td class="header-right">
                REPUBLIC OF CAMEROON<br>
                <span class="motto">Peace - Work - Fatherland</span><br>
                -------<br>
                MINISTRY OF HIGHER EDUCATION
            </td>
        </tr>
    </table>

    <div class="document-title">
        <h1>LISTE DES UTILISATEURS</h1>
    </div>

    <table class="info-grid">
        <tr>
            <td class="info-item">
              <span class="label">Année Académique :</span>
{{ $annee->date_debut ? $annee->date_debut->format('Y') . '-' . ($annee->date_debut->format('Y') + 1) : 'N/A' }}
<br>

                <span class="label">Imprimé le :</span> {{ now()->format('d/m/Y H:i') }}
            </td>
            <td class="info-item" style="text-align: right;">
                <span class="label">Rôle :</span> {{ $role }}<br>
                <span class="label">Total :</span> {{ $users->count() }} enregistrement(s)
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Matricule</th>
                <th width="30%">Nom Complet</th>
                <th width="25%">Email</th>
                <th width="10%">Genre</th>
                <th width="10%">Rôle</th>
                <th width="10%">Spécialité</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="font-weight: bold;">{{ $user->matricule }}</td>
                <td>{{ strtoupper($user->name) }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->sexe }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->specialite ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Aucun utilisateur trouvé pour ces critères.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Système de Gestion Académique - Page <script type="text/php">echo $PAGE_NUM . " / " . $PAGE_COUNT;</script>
    </div>

</body>
</html>
