export class Errors {
    private data: { [fieldName: string]: string[] } = {};
    
    public get(fieldName: string): string[] {
        return this.data[fieldName];
    }

    public has(fieldName: string): boolean {
        return this.data[fieldName] && this.data[fieldName].length > 0;
    }

    public clear(fieldName: string) {
        delete this.data[fieldName];
    }

    public load(data: { [fieldName: string]: string[] }) {
        this.data = data;
    }
}