<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\TranslationBundle\DataFixtures\AbstractTranslatableEntityFixture;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;

/**
 * @codeCoverageIgnore
 */
class LoadIssueResolutionData extends AbstractTranslatableEntityFixture
{
    const ISSUE_RESOLUTION_PREFIX = 'resolution';

    /**
     * @var array
     */
    protected $issueResolutions = [
        IssueResolution::FIXED,
        IssueResolution::WONT_FIX,
        IssueResolution::DUPLICATE,
        IssueResolution::INCOMPLETE,
        IssueResolution::CANNOT_REPRODUCE,
        IssueResolution::DONE,
        IssueResolution::WONT_DO,
        IssueResolution::REJECTED,
    ];

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function loadEntities(ObjectManager $manager)
    {
        $issueResolutionRepository = $manager->getRepository('OroAcademicSimpleBTSBundle:IssueResolution');
        $translationLocales = $this->getTranslationLocales();

        foreach ($translationLocales as $locale) {
            foreach ($this->issueResolutions as $resolutionName) {
                $issueResolution = $issueResolutionRepository->findOneBy(['name' => $resolutionName]);
                if (!$issueResolution) {
                    $issueResolution = new IssueResolution($resolutionName);
                }

                $issueResolutionLabel = $this->translate($resolutionName, static::ISSUE_RESOLUTION_PREFIX, $locale);
                $issueResolution
                    ->setLocale($locale)
                    ->setLabel($issueResolutionLabel);

                $manager->persist($issueResolution);
            }

            $manager->flush();
        }
    }
}
