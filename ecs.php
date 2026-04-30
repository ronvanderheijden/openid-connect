<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\ArrayIndentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\DisallowLongArraySyntaxSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterCastSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Files\FileHeaderSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\CastSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use SlevomatCodingStandard\Sniffs\Arrays\TrailingArrayCommaSniff;
use SlevomatCodingStandard\Sniffs\Classes\ClassConstantVisibilitySniff;
use SlevomatCodingStandard\Sniffs\Classes\ClassMemberSpacingSniff;
use SlevomatCodingStandard\Sniffs\Classes\ConstantSpacingSniff;
use SlevomatCodingStandard\Sniffs\Classes\EmptyLinesAroundClassBracesSniff;
use SlevomatCodingStandard\Sniffs\Classes\PropertySpacingSniff;
use SlevomatCodingStandard\Sniffs\Classes\RequireConstructorPropertyPromotionSniff;
use SlevomatCodingStandard\Sniffs\Classes\TraitUseDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\DeadCatchSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireTrailingCommaInCallSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireTrailingCommaInDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseFromSameNamespaceSniff;
use SlevomatCodingStandard\Sniffs\PHP\OptimizedFunctionsWithoutUnpackingSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->disableParallel();
    $ecsConfig->paths([__DIR__]);
    $ecsConfig->sets([
        SetList::PSR_12,
    ]);

    $ecsConfig->rule(FileHeaderSniff::class);
    $ecsConfig->rule(TraitUseDeclarationSniff::class);
    $ecsConfig->rule(DisallowLongArraySyntaxSniff::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(UnusedUsesSniff::class);
    $ecsConfig->rule(UseFromSameNamespaceSniff::class);
    $ecsConfig->rule(OptimizedFunctionsWithoutUnpackingSniff::class);
    $ecsConfig->rule(DeadCatchSniff::class);
    $ecsConfig->rule(RequireTrailingCommaInCallSniff::class);
    $ecsConfig->rule(RequireTrailingCommaInDeclarationSniff::class);
    $ecsConfig->rule(RequireConstructorPropertyPromotionSniff::class);
    $ecsConfig->rule(AlphabeticallySortedUsesSniff::class);
    $ecsConfig->rule(ClassConstantVisibilitySniff::class);
    $ecsConfig->rule(TrailingArrayCommaSniff::class);
    $ecsConfig->rule(ArrayIndentSniff::class);
    $ecsConfig->rule(ClassMemberSpacingSniff::class);
    $ecsConfig->rule(CastSpacingSniff::class);
    $ecsConfig->rule(SpaceAfterCastSniff::class);
    $ecsConfig->ruleWithConfiguration(LineLengthSniff::class, [
        'absoluteLineLimit' => 120,
    ]);
    $ecsConfig->ruleWithConfiguration(FunctionSpacingSniff::class, [
        'spacing' => 1,
        'spacingBeforeFirst' => 0,
        'spacingAfterLast' => 0,
    ]);
    $ecsConfig->ruleWithConfiguration(PropertySpacingSniff::class, [
        'minLinesCountBeforeWithComment' => 1,
        'maxLinesCountBeforeWithComment' => 1,
        'minLinesCountBeforeWithoutComment' => 0,
        'maxLinesCountBeforeWithoutComment' => 1,
    ]);
    $ecsConfig->ruleWithConfiguration(ConstantSpacingSniff::class, [
        'minLinesCountBeforeWithComment' => 1,
        'maxLinesCountBeforeWithComment' => 1,
        'minLinesCountBeforeWithoutComment' => 0,
        'maxLinesCountBeforeWithoutComment' => 1,
    ]);
    $ecsConfig->ruleWithConfiguration(EmptyLinesAroundClassBracesSniff::class, [
        'linesCountAfterOpeningBrace' => 0,
        'linesCountBeforeClosingBrace' => 0,
    ]);
    $ecsConfig->ruleWithConfiguration(BinaryOperatorSpacesFixer::class, [
        'default' => BinaryOperatorSpacesFixer::SINGLE_SPACE,
    ]);
};
