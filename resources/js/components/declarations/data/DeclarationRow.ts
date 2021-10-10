import moment from 'moment';

export type DeclarationType = 'pay'|'gift'|'pay-biomeat';
export interface IDeclarationRow {
    amount: string;
    description: string;
    date: string;
    type: DeclarationType;
    file?: File;
}

export class DeclarationRow implements IDeclarationRow {
    public static Empty(): DeclarationRow {
        return new DeclarationRow;
    }

    public static ForFile(file: File): DeclarationRow {
        const row = new DeclarationRow();
        row.file = file;
        return row;
    }

    public static FromDeclaration(row: DeclarationRow): DeclarationRow {
        const newRow = new DeclarationRow();
        newRow.file = row.file;
        newRow.amount = row.amount;
        newRow._date = row._date.clone();
        newRow.description = row.description;
        newRow.type = row.type;
        return newRow;
    }

    public amount: string = '0.00';
    public description: string = '';
    public type: DeclarationType = 'pay';
    public file?: File;
    private _date: moment.Moment = moment();

    public get fileName() {
        return this.file ? this.file.name : '( Geen )';
    }

    public get date(): string {
        return this._date.format('YYYY-MM-DD');
    }

    public set date(v: string) {
        this._date = moment(v);
    }
}
