<?php

declare(strict_types=1);

namespace App\Presenters;

use App\BasePresenter;
use App\Helper\PdfGenerator;
use App\Model\Invoice;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;


final class InvoicePresenter extends BasePresenter
{
    /** @var Invoice @inject */
    public Invoice $invoiceModel;


    public function actionDefault(): void
    {
        $this->template->invoices = $this->invoiceModel->getInvoices();
    }

    public function actionEdit($id): void
    {
        if ($id) {
            $invoice = $this->template->invoice = $this->invoiceModel->getInvoice($id);
            $this->setInvoiceDefaults($invoice);
        }
    }

    private function setInvoiceDefaults($defaults): void
    {
        $defaults['client'] = $defaults['client_id'];
        $defaults['issue_date'] = $defaults['issue_date']->format('Y-m-d');
        $defaults['due_date'] = $defaults['due_date']->format('Y-m-d');
        unset($defaults['client_id']);
        $this['invoiceForm']->setDefaults($defaults);
        $this->template->description = $defaults['description'];
        $this->template->hours_sum = $defaults['hours_sum'];
        $this->template->charge_per_hour = $defaults['charge_per_hour'];
    }

    public function createComponentInvoiceForm(): Form
    {
        $form = new Form();

        $form->addText('title','Title')->setRequired();

        $form->addSelect('client', 'Client:', $this->client->getClientNames())
            ->setRequired();

        $form->addText('issue_date', 'Issue date', 50, 255)
            ->setHtmlType('date')->setRequired();
        $form->addText('due_date', 'Due date', 50, 255)
            ->setHtmlType('date')->setRequired();

        $form->addInteger('var_symbol', 'Variable symbol');

        $form->addSubmit('submit', 'Save');
        $form->onSuccess[] = [$this, 'invoiceFormSubmitted'];

        return $form;
    }

    /**
     * @throws AbortException
     */
    public function invoiceFormSubmitted(Form $form): void
    {
        $data = (object) $form->getHttpData();

        try {
            if (!$this->getParameter('id')) {
                $this->invoiceModel->addInvoice($this->user->id, $data);
                $this->flashMessage('Invoice successfully created.', 'success');
            }

            if ($this->getParameter('id')) {
                $this->invoiceModel->editInvoice($this->getParameter('id'), $data);
                $this->flashMessage('Invoice successfully updated.', 'success');
            }
        }
        catch (Exception $e) {
//            $this->flashMessage('Something went wrong', "danger");
            $this->flashMessage($e->getMessage());
            $this->redirect('this');
        }
        finally {
            $this->redrawControl('flashMessages');
            $this->redirect('Invoice:');
        }
    }


    /**
     * @throws ,MpdfException
     */
    public function handleDownloadInvoice($id): void
    {
        $invoice = $this->invoiceModel->getInvoice($id);
        $fileName = strtolower(Strings::Webalize($this->contractor->last_name)).'_'.$invoice->title;

        $dataForPdfInvoice = [$fileName, $this->contractor->toArray(), $this->clients[$invoice->client_id]->toArray(), $invoice];
        PdfGenerator::createInvoicePdf(...$dataForPdfInvoice);
    }

    /**
     * @throws AbortException
     */
    public function handleDeleteInvoice($id): void
    {
        try {
            $this->invoiceModel->deleteInvoice($id);
            $this->flashMessage('Invoice successfully deleted', 'success');
        } catch (Exception $e) {
//            $this->flashMessage("Something went wrong", "danger");
            $this->flashMessage($e->getMessage());
        }
        finally {
            $this->redrawControl('flashMessages');
            $this->redirect('this');
        }
    }
}
