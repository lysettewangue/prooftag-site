<?php

declare(strict_types=1);

namespace App\Enums;

enum SecurityPoint: int
{
    case RegistrationCheck = 1;
    case MintDataUpdate = 2;
    case TestBenchCollection = 3;
    case Photograph = 4;
    case GpsLocation = 5;
    case VisualInspectionTablet = 6;
    case ReportGeneration = 7;
    case DecisionGeneration = 8;
    case SecuredPvPrint = 9;
    case VignetteSeal = 10;
    case MintCentralisation = 11;
    case FieldControl = 12;

    /**
     * @return list<int>
     */
    public static function orders(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array{fr: string, en: string}
     */
    public function titles(): array
    {
        return match ($this) {
            self::RegistrationCheck => [
                'fr' => 'Vérification des informations de la carte grise sur la base du MINT',
                'en' => 'Verification of registration-card data against the MINT database',
            ],
            self::MintDataUpdate => [
                'fr' => 'Mise à jour des données dans les bases centrales du MINT',
                'en' => 'Update of data in the MINT central databases',
            ],
            self::TestBenchCollection => [
                'fr' => 'Collecte automatique des données des bancs de tests par GIEGLAN',
                'en' => 'Automatic collection of test-bench data by GIEGLAN',
            ],
            self::Photograph => [
                'fr' => 'Prise de photo',
                'en' => 'Photograph capture',
            ],
            self::GpsLocation => [
                'fr' => 'Localisation par GPS',
                'en' => 'GPS location',
            ],
            self::VisualInspectionTablet => [
                'fr' => 'Utilisation de la tablette pour le contrôle visuel',
                'en' => 'Tablet use for visual inspection',
            ],
            self::ReportGeneration => [
                'fr' => 'Génération automatique du PV et rapport de test',
                'en' => 'Automatic generation of the inspection report and test report',
            ],
            self::DecisionGeneration => [
                'fr' => 'Génération automatique de la décision',
                'en' => 'Automatic generation of the decision',
            ],
            self::SecuredPvPrint => [
                'fr' => 'Impression du résultat sur exemplaire de PV sécurisé',
                'en' => 'Printing the result on a secured inspection-report copy',
            ],
            self::VignetteSeal => [
                'fr' => 'Sécurisation de la vignette avec le scellé à bulles et le QR code',
                'en' => 'Securing the vignette with the bubble seal and QR code',
            ],
            self::MintCentralisation => [
                'fr' => 'Centralisation des données dans le serveur du MINT',
                'en' => 'Centralisation of data on the MINT server',
            ],
            self::FieldControl => [
                'fr' => 'Contrôle formel et/ou informel sur le terrain',
                'en' => 'Formal and/or informal field control',
            ],
        };
    }
}
