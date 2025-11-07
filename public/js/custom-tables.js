/**
 * Sistema de tablas personalizadas - Reemplazo para DataTables
 * Funcionalidades: búsqueda, ordenación, paginación, acciones
 */

class CustomTable {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.table = document.getElementById(tableId);
        this.data = [];
        this.filteredData = [];
        this.currentPage = 1;
        this.itemsPerPage = options.itemsPerPage || 10;
        this.sortColumn = options.sortColumn || 0;
        this.sortDirection = options.sortDirection || 'asc';
        this.searchTerm = '';
        
        this.options = {
            url: options.url || '',
            columns: options.columns || [],
            onLoad: options.onLoad || function() {},
            onEdit: options.onEdit || function() {},
            onDelete: options.onDelete || function() {},
            onActivate: options.onActivate || function() {},
            onDeactivate: options.onDeactivate || function() {}
        };
        
        this.init();
    }
    
    init() {
        this.loadData();
    }
    
    async loadData() {
        try {
            const response = await fetch(this.options.url);
            if (!response.ok) throw new Error('Error al cargar datos');
            
            const result = await response.json();
            this.data = result.aaData || result.data || result;
            this.filteredData = [...this.data];
            
            this.renderTable();
            this.options.onLoad();
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }
    
    renderTable() {
        const tbody = this.table.querySelector('tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        this.filteredData.forEach((row, index) => {
            const tr = document.createElement('tr');
            
            // Renderizar cada columna
            this.options.columns.forEach((column, colIndex) => {
                const td = document.createElement('td');
                
                if (column.render) {
                    td.innerHTML = column.render(row[colIndex], row, index);
                } else {
                    td.textContent = row[colIndex] || '';
                }
                
                tr.appendChild(td);
            });
            
            tbody.appendChild(tr);
        });
    }
    
    refresh() {
        this.loadData();
    }
    
    edit(id) {
        this.options.onEdit(id);
    }
    
    delete(id) {
        this.options.onDelete(id);
    }
    
    activate(id) {
        this.options.onActivate(id);
    }
    
    deactivate(id) {
        this.options.onDeactivate(id);
    }
}

// Objeto global para manejar múltiples tablas
window.customTables = {};

// Función auxiliar para inicializar tabla
function initCustomTable(tableId, options) {
    customTables[tableId] = new CustomTable(tableId, options);
    return customTables[tableId];
}

// Función para mostrar confirmaciones
function showConfirm(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Función para mostrar alertas
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
    `;
    alertDiv.textContent = message;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}